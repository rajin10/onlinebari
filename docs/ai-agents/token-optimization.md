# Token optimization for AI agents

> "AI agents take too many tokens in Messages compared to other tools." This page explains why
> that happens and what actually moves the number — ranked by impact, not by ease.

## TL;DR — the one principle

**Everything that sits in context is re-sent on every single turn.** An agent doesn't send your
message once; each step re-sends the *entire* conversation so far — system prompt, every injected
instruction file, every tool schema, every prior message, and every file/command output it has
read. So the cost of a turn isn't what the agent *says* — it's the size of everything it has to
*re-read to say it*. Optimize for a small, clean context, and the per-turn cost falls for the rest
of the session.

That reframes "Messages take too many tokens": the **Messages** category is large not because the
agent is chatty, but because the running transcript (which lives under Messages) is re-billed every
turn and grows monotonically. Trimming prose helps a little; trimming what gets injected and
re-read helps a lot.

## Where the tokens actually go

A rough ranking for a typical coding turn, largest first:

1. **Conversation history, re-sent every turn.** The dominant cost in any session longer than a
   few turns. Every file you've read and every command output stays in the transcript and is
   re-billed on each subsequent turn until the context is compacted or cleared.
2. **Tool results.** A single `Read` of a 2,000-line file or an unfiltered `grep`/log dump can
   outweigh dozens of chat messages — and then it sits in history (see #1).
3. **Injected context (per turn, fixed-ish).** The harness system prompt + every always-loaded
   instruction file (`AGENTS.md` and each pointer) + connected **MCP server instructions** + the
   **tool schemas** the model is told it can call. Each connected MCP server adds its instructions
   *and* the JSON schema of every one of its tools to every turn.
4. **The agent's own prose (Messages output).** Usually the *smallest* slice — but the one people
   notice because it's the visible part.

The practical lesson: most "the agent is expensive" problems are #1–#3, not #4. A guide that only
tightened prose would miss the target, which is why the levers below start at the top of the list.

## Levers, ranked by impact

### A. Shrink what's injected every turn (biggest, set-and-forget)

- **Keep `AGENTS.md` lean.** Its byte size is a tax on every turn for every native tool. Terse
  bullets, no prose padding. (Windsurf even hard-caps rule files at 12,000 chars.)
- **Create the minimum number of pointer files.** Every pointer is injected for *its* tool every
  turn. This is why we rely on native `AGENTS.md` reading wherever possible and deleted the
  redundant `.cursor/rules/agents.mdc` — see [README.md](README.md). Fewer files = fewer tokens
  *and* less duplication.
- **Disconnect MCP servers you aren't using.** This is often the single biggest injected-context
  win. Each connected server ships its instructions plus a full tool schema for every tool, on
  every turn — a large multi-tool server can cost more per turn than `AGENTS.md` itself. Connect
  servers per-task, not globally.
- **Prefer deferred / searchable tool loading.** When the harness supports loading tool schemas on
  demand (e.g. a tool-search step) instead of front-loading every schema, use it — only the tools
  you actually call get their schema billed.

### B. Keep the transcript small (per-action discipline)

- **Read narrowly.** Use line ranges / offsets and targeted `grep` instead of reading whole files.
  Don't read a file you've already read.
- **Filter command output.** Pipe to `head`, grep for the relevant lines, summarize — don't let a
  full test log or `npm`/`composer` dump land in context verbatim.
- **Use subagents for broad exploration.** A subagent burns tokens searching in *its own* context
  and returns only the conclusion. The dozens of file reads never enter the main transcript. This
  is the highest-leverage habit for research-heavy work.
- **Reference, don't reproduce.** Cite `app/Models/Order.php:42`; don't paste the code back into a
  message where it gets re-billed every following turn.
- **Compact or clear between unrelated tasks.** Starting a fresh task on a stale, huge context pays
  the full history cost for no benefit. Reset when the topic changes.

### C. Trim output prose (smallest, but free)

- The `Output rules` in [`AGENTS.md`](../../AGENTS.md) already enforce this: act first, no
  preamble/postamble, ≤4 lines by default, no completion summaries, no restating tool output.
- It's the most *visible* lever and the easiest to keep enabled, but expect single-digit-percent
  savings — the wins in A and B are larger.

## Checklist

Setup (once):

- [ ] `AGENTS.md` is terse and the only place rules live.
- [ ] No redundant pointer files — native tools get nothing; non-native tools get a thin pointer.
- [ ] Unused MCP servers are disconnected; long reference docs live in `docs/`, not in `AGENTS.md`.

Per session / per task:

- [ ] Read with ranges and targeted greps; never re-read a file.
- [ ] Filter long command output before it enters context.
- [ ] Delegate broad searches to a subagent.
- [ ] Reference paths instead of pasting code/output.
- [ ] Compact or clear context when switching tasks.
- [ ] Keep prose minimal per the output rules.

## What we changed in this repo

- Rewrote `AGENTS.md` as a single lean source of truth (output rules + professional rules + project).
- Deleted `.cursor/rules/agents.mdc` — Cursor reads `AGENTS.md` natively, so it was a duplicate
  injected on every Cursor turn.
- Fixed `GEMINI.md` to import `AGENTS.md` via `@./AGENTS.md` — Gemini CLI loads context through
  `@./` imports rather than the Markdown link the file previously used.
- Added pointers only where a tool can't read `AGENTS.md` natively (`.aider.conf.yml`,
  `.github/copilot-instructions.md`), each as thin as the tool allows.

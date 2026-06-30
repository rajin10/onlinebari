# AI agents in this repo

All agent rules live in one file: **[`AGENTS.md`](../../AGENTS.md)** at the repo root. Everything
else here is either a thin pointer to it or human-facing reference. This page explains *why* the
files are arranged the way they are, and how to add a new agent without creating duplication.

- **[token-optimization.md](token-optimization.md)** — where tokens actually go in an agent
  session and how to cut them. Read this if an agent feels expensive.

## The one rule

`AGENTS.md` is the **single source of truth**. Rules are written there and nowhere else. A pointer
file may carry a one-line "be terse" safety net (see below), but never a copy of the real rules —
duplicated rules drift out of sync and cost tokens on every turn.

## Coverage matrix

`AGENTS.md` has become a cross-vendor open standard ([agents.md](https://agents.md)), so most tools
read it directly. A pointer file is only created for the tools that *don't*.

| Agent | Reads `AGENTS.md` natively? | File in this repo | Why |
|---|---|---|---|
| **OpenAI Codex** | ✅ yes | — | Root `AGENTS.md` auto-read (Codex originated the standard). |
| **Cursor** | ✅ yes | — | Agent reads root + nested `AGENTS.md` automatically. A `.cursor/rules/*.mdc` pointer would be redundant and inject every turn — we deleted ours. |
| **Windsurf / Cascade** | ✅ yes | — | Root `AGENTS.md` is auto-discovered and treated as an always-on rule. |
| **Cline** | ✅ yes | — | Root `AGENTS.md` is merged with `.clinerules/` automatically. |
| **GitHub Copilot** | ⚠️ partial | [`.github/copilot-instructions.md`](../../.github/copilot-instructions.md) | VS Code chat (default-on), the coding agent, code review, and the CLI read `AGENTS.md`. JetBrains / Visual Studio / Eclipse / Xcode do **not** — the pointer covers them. |
| **Gemini CLI** | ❌ no | [`GEMINI.md`](../../GEMINI.md) | Default context file is `GEMINI.md`; it loads `AGENTS.md` via the `@./AGENTS.md` import (Markdown links are ignored). |
| **Aider** | ❌ no | [`.aider.conf.yml`](../../.aider.conf.yml) | Loads nothing by filename. `read: AGENTS.md` wires it in as a read-only, cache-friendly file. |
| **Claude Code** | ❌ no (holdout) | [`CLAUDE.md`](../../CLAUDE.md) | Reads `CLAUDE.md`, which `@AGENTS.md`-imports the canonical file. |

Native support was verified against each tool's official docs in 2026; this corner of the ecosystem
moves fast, so re-check before assuming a pointer is still needed (or still missing).

## Pointer patterns

Three ways a non-native tool pulls in `AGENTS.md`, in order of preference:

1. **Import** — the pointer's body is essentially just an import directive, no rule duplication.
   `CLAUDE.md` → `@AGENTS.md` (pure import). `GEMINI.md` → `@./AGENTS.md` — Gemini CLI loads context
   through `@./` imports rather than Markdown links, so the `./` prefix matters; it also carries a
   one-line terseness fallback in case the import doesn't resolve.
2. **Config reference** — point the tool's config at the file. `.aider.conf.yml` → `read: AGENTS.md`.
3. **Deliberate safety line** — when a tool can't reliably import (Copilot's legacy IDEs follow
   neither imports nor links), the pointer carries a *minimal* inline restatement of the highest-value
   rules plus "read AGENTS.md." This is the one sanctioned duplication; keep it to a few lines.

## Adding a new agent

1. Check [agents.md](https://agents.md) and the tool's own docs: does it read root `AGENTS.md`
   natively? If yes — **do nothing**, it already works.
2. If no, find its native instruction file/config and add the **thinnest** pointer that loads
   `AGENTS.md` (import > config reference > safety line).
3. Add a row to the matrix above. Never copy rule content into the new file.

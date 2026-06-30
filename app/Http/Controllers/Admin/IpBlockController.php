<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpBlock;
use Illuminate\Http\Request;

class IpBlockController extends Controller
{
    public function index()
    {
        $ipBlocks = IpBlock::latest()->paginate(20);
        return view('admin.ip_block.index', compact('ipBlocks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ip_address' => 'required|ip|unique:ip_blocks,ip_address',
            'reason' => 'nullable|string|max:255',
        ]);

        IpBlock::create($data);

        notify()->success('IP Blocked Successfully');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $ipBlock = IpBlock::findOrFail($id);
        $ipBlock->delete();

        notify()->success('IP Unblocked Successfully');
        return redirect()->back();
    }
}

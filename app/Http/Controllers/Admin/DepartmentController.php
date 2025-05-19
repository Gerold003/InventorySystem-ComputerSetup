<?php

// app/Http/Controllers/Admin/DepartmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with(['head', 'users'])
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return view('admin.departments.index', compact('departments'));
    }
    
    public function create()
    {
        return view('admin.departments.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id'
        ]);
        
        Department::create($validated);
        
        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully');
    }
    
    public function show(Department $department)
    {
        $department->load(['head', 'users']);
        return view('admin.departments.show', compact('department'));
    }
    
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }
    
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id'
        ]);
        
        $department->update($validated);
        
        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully');
    }
    
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully');
    }
}
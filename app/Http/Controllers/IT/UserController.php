<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          // Fetch all users
          $users = User::all();
          $data = [];
          
          foreach ($users as $user) {
      
              // Define the Edit button (use the route for editing user)
              $btnEdit = '<a href=" " class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                  <i class="fa fa-lg fa-fw fa-pen"></i>
              </a>';
      
              // Define the Delete button (trigger modal for deletion)
              $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" id="deleteUserID" title="Delete" data-delete="' . $user->id . '" data-toggle="modal" data-target="#deleteModal">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';
      
              // Build the row data for the DataTable
              $rowData = [
                  $user->id,
                  $user->name,
                  $user->email,
                  $user->roles->pluck('name')->implode(', '),  // Assuming roles are set with Spatie
                  // $user->status,  // Assuming you have a status field
                  $user->created_at->format('m/d/Y'),
                  '<nobr>' . $btnEdit . $btnDelete . '</nobr>',  // Action buttons (Edit and Delete)
              ];
      
              $data[] = $rowData;  // Add the row data to the $data array
          }
      
          // Pass the processed data to the view
          return view('IT.user.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

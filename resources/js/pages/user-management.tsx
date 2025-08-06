"use client"

import * as React from "react"
import { useState } from "react";
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/react';
import { Button } from "@/components/ui/button";
import { ChevronDown } from 'lucide-react';

import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table"

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Accounts',
        href: '/admin/users',
    },
];

export default function userManagement({ users }) {
    const { auth, flash } = usePage().props;
    const [editingUsers, setEditingUsers] = useState({});

    // State to manage the delete confirmation modal
    const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
    const [userToDelete, setUserToDelete] = useState(null);

    const { data, setData, put, processing, errors } = useForm({
        role_id: '',
    });

    const { delete: inertiaDelete, processing: isDeleting } = useForm({});

    // This function will now update the form's state directly
    const handleRoleChange = (newRoleId) => {
        setData('role_id', newRoleId);
    };

    // Helper function to convert the role name/id into a more readable format
    const getRoleDisplayName = (role) => {
        const roleValue = typeof role === 'object' ? role.role_name : role;
        switch (roleValue) {
            case 1:
            case 'system-admin':
                return 'System Administrator';
            case 2:
            case 'ministry-leader':
                return 'Ministry Leader';
            case 3:
            case 'ministry-staff':
                return 'Ministry Staff';
            case 4:
            case 'church-member':
                return 'Church Member';
            default:
                return 'No Role Assigned';
        }
    };

    const toggleEditMode = (userId, currentRoleId) => {
        setEditingUsers(prev => ({
            ...prev,
            [userId]: !prev[userId],
        }));
        if (!editingUsers[userId]) {
            // When entering edit mode, set the form state to the current role ID
            setData('role_id', currentRoleId || '');
        } else {
            // When exiting edit mode, reset the form state
            setData('role_id', '');
        }
    };

    const saveChanges = (userId) => {
        // We now check if the role_id in the form data is a valid number
        if (data.role_id) {
            // The put method will send the current form data automatically
            put(route('admin.users.update', { user: userId }), {
                onSuccess: () => {
                    // On success, exit edit mode and reset the form state
                    toggleEditMode(userId);
                },
                onError: (errors) => {
                    console.error('Failed to update user role:', errors);
                }
            });
        } else {
            // If no role is selected, simply exit edit mode
            toggleEditMode(userId);
        }
    };

    // Function to open the delete confirmation modal
    const handleDeleteClick = (user) => {
        setUserToDelete(user);
        setIsDeleteModalOpen(true);
    };

    // Function to close the delete confirmation modal
    const cancelDelete = () => {
        setUserToDelete(null);
        setIsDeleteModalOpen(false);
    };

    // Function to handle the actual deletion
    const deleteUser = () => {
        if (userToDelete) {
            inertiaDelete(route('admin.users.destroy', { user: userToDelete.id }), {
                onSuccess: () => {
                    // On success, close the modal
                    cancelDelete();
                },
                onError: (errors) => {
                    console.error('Failed to delete user:', errors);
                    // Still close the modal even if there's an error
                    cancelDelete();
                },
            });
        }
    };

    // Helper to check if any user is being edited
    const isAnyUserEditing = Object.values(editingUsers).some(isEditing => isEditing);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="User Management" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                {/* Display success and error messages */}
                {flash?.success && (
                    <div className="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                        <p>{flash.success}</p>
                    </div>
                )}
                {flash?.error && (
                    <div className="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                        <p>{flash.error}</p>
                    </div>
                )}
                <Table>
                    <TableCaption>A list of registered users</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Username</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Joined Date</TableHead>
                            <TableHead>System Privileges</TableHead>
                            <TableHead>Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {users.map((user) => (
                            <TableRow key={user.id}>
                                <TableCell className="font-medium">{user.id}</TableCell>
                                <TableCell>{user.username}</TableCell>
                                <TableCell>{user.email}</TableCell>
                                <TableCell>{new Date(user.created_at).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                })}</TableCell>
                                <TableCell>
                                    {editingUsers[user.id] ? (
                                        <DropdownMenu>
                                            <DropdownMenuTrigger asChild>
                                                <Button variant="outline">
                                                    {getRoleDisplayName(data.role_id) || getRoleDisplayName(user.role?.role_name) || 'No Role Assigned'}
                                                    <ChevronDown className="ml-2 h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent>
                                                <DropdownMenuRadioGroup
                                                    value={data.role_id || ''}
                                                    onValueChange={handleRoleChange}
                                                >
                                                    <DropdownMenuRadioItem value={1}>System Administrator</DropdownMenuRadioItem>
                                                    <DropdownMenuRadioItem value={2}>Ministry Leader</DropdownMenuRadioItem>
                                                    <DropdownMenuRadioItem value={3}>Ministry Staff</DropdownMenuRadioItem>
                                                    <DropdownMenuRadioItem value={4}>Church Member</DropdownMenuRadioItem>
                                                </DropdownMenuRadioGroup>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    ) : (
                                        <span>{getRoleDisplayName(user.role?.role_name) || 'No Role Assigned'}</span>
                                    )}
                                </TableCell>
                                <TableCell>
                                    {/* Conditional rendering: Do not show buttons for the logged-in user */}
                                    {user.id !== auth.user.id && (editingUsers[user.id] || !isAnyUserEditing) ? (
                                        <>
                                            {editingUsers[user.id] ? (
                                                <>
                                                    <Button
                                                        variant="outline"
                                                        className='mr-1 bg-green-500 text-stone-50 hover:bg-green-600'
                                                        onClick={() => saveChanges(user.id)}
                                                        disabled={processing || data.role_id === user.role?.id}
                                                    >
                                                        {processing ? 'Saving...' : 'Save'}
                                                    </Button>
                                                    <Button
                                                        variant="outline"
                                                        className='bg-red-500 text-stone-50 hover:bg-red-600'
                                                        onClick={() => toggleEditMode(user.id, user.role?.id)}
                                                        disabled={processing}
                                                    >
                                                        Cancel
                                                    </Button>
                                                </>
                                            ) : (
                                                <>
                                                    <Button
                                                        variant="outline"
                                                        className='mr-1 bg-blue-600 text-stone-50 hover:bg-blue-700'
                                                        onClick={() => toggleEditMode(user.id, user.role?.id)}
                                                    >
                                                        Edit
                                                    </Button>
                                                    <Button
                                                        variant="outline"
                                                        className='bg-red-500 text-stone-50 hover:bg-red-600'
                                                        onClick={() => handleDeleteClick(user)}
                                                        disabled={isDeleting}
                                                    >
                                                        Delete
                                                    </Button>
                                                </>
                                            )}
                                        </>
                                    ) : null}
                                </TableCell>
                            </TableRow>
                        ))}
                        {users.length === 0 && (
                            <TableRow>
                                <TableCell colSpan={6} className="text-center">
                                    No users found.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
            {/* Delete Confirmation Modal */}
            {isDeleteModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
                    <div className="bg-white p-6 rounded-lg shadow-xl text-center">
                        <h3 className="text-lg font-bold mb-4">Confirm Deletion</h3>
                        <p className="mb-6">
                            Are you sure you want to delete the user <span className="font-semibold">{userToDelete?.username}</span>? This action cannot be undone.
                        </p>
                        <div className="flex justify-center gap-4">
                            <Button
                                variant="outline"
                                className='bg-red-500 text-stone-50 hover:bg-red-600'
                                onClick={deleteUser}
                                disabled={isDeleting}
                            >
                                {isDeleting ? 'Deleting...' : 'Confirm Delete'}
                            </Button>
                            <Button
                                variant="outline"
                                className='bg-gray-200 text-gray-800 hover:bg-gray-300'
                                onClick={cancelDelete}
                                disabled={isDeleting}
                            >
                                Cancel
                            </Button>
                        </div>
                    </div>
                </div>
            )}
        </AppLayout >
    );
}
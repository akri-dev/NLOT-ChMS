"use client"

import * as React from "react"
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
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
        title: 'User Management',
        href: '/admin/users',
    },
];

export default function userManagement({ users }) {

    const [position, setPosition] = React.useState("bottom")
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="User Management" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <Table>
                    <TableCaption>A list of registered users</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Username</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Joined Date</TableHead>
                            <TableHead>System Privileges</TableHead>
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
                                    <DropdownMenu>
                                        <DropdownMenuTrigger>
                                            <Button variant="outline">
                                                {user.role
                                                    ? user.role.role_name === 'system-admin'
                                                        ? 'System Administrator'
                                                        : user.role.role_name === 'ministry-leader'
                                                            ? 'Ministry Leader'
                                                            : user.role.role_name === 'ministry-staff'
                                                                ? 'Ministry Leader'
                                                                : 'Church Member '
                                                    : 'No Role Assigned'}
                                                <ChevronDown />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent>
                                            <DropdownMenuRadioGroup value={position} onValueChange={setPosition}>
                                                <DropdownMenuRadioItem value="top">Top</DropdownMenuRadioItem>
                                                <DropdownMenuRadioItem value="bottom">Bottom</DropdownMenuRadioItem>
                                                <DropdownMenuRadioItem value="right">Right</DropdownMenuRadioItem>
                                            </DropdownMenuRadioGroup>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>


                                <TableCell>
                                    <Button variant="outline" className='mr-1 bg-blue-600 text-stone-50 hover:cursor-pointer'>Edit</Button>
                                    <Button variant="outline" className='bg-red-500 text-stone-50 hover:cursor-pointer'>Delete</Button>
                                </TableCell>
                            </TableRow>
                        ))}
                        {users.length === 0 && (
                            <TableRow>
                                <TableCell colSpan={4} className="text-center">
                                    No users found.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
        </AppLayout >
    );
}

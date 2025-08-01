<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    public function share(Request $request)
    {
        
        // return array_merge(parent::share($request), [
        //     'auth' => [
        //         'user' => $request->user() ? [
        //             'id' => $request->user()->id,
        //             'username' => $request->user()->username,
        //             'email' => $request->user()->email,
        //             // Add the user's role name here
        //             // Ensure your User model has a 'role' relationship defined
        //             // e.g., public function role() { return $this->belongsTo(Role::class); }
        //             'role_name' => $request->user()->role ? $request->user()->role->role_name : null,
        //         ] : null,
        //     ],
        //     'ziggy' => function () use ($request) {
        //         return array_merge((new Ziggy)->toArray(), [
        //             'location' => $request->url(),
        //         ]);
        //     },
        // ]);
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'username' => $request->user()->username,
                    'email' => $request->user()->email,
                    'role_name' => $request->user()->role ? $request->user()->role->role_name : null,
                ] : null,
            ],
            // This is the key part that shares the flash messages with the frontend.
            // When you use `return redirect()->back()->with('success', '...');`
            // this middleware will make that message available in React via `usePage().props.flash.success`.
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
import { useCallback } from 'react';

export function useInitials() {
    // Modify the type of fullName to explicitly allow null or undefined
    return useCallback((userName: string | null | undefined): string => {
        // Line 5: Add a check to ensure fullName is a string.
        // If fullName is null or undefined, default to an empty string.
        const safeFullName = userName || ''; // This is the key change

        const names = safeFullName.trim().split(' ');

        if (names.length === 0) return '';
        if (names.length === 1) return names[0].charAt(0).toUpperCase();

        const firstInitial = names[0].charAt(0);
        const lastInitial = names[names.length - 1].charAt(0);

        return `${firstInitial}${lastInitial}`.toUpperCase();
    }, []);
}
export interface Auth {
    user: User;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    auth: Auth;
    links: {
        linkedin: string | null;
        github: string | null;
    };
    flash: {
        success: string | null;
        error: string | null;
        info: string | null;
        warning: string | null;
    };
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

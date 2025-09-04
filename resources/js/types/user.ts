export type Professional = {
    id: number;
    speciality: string;
    description: string;
    location: string;
}

export type User = {
    id: number;
    name: string;
    email: string;
    professional: Professional;
}
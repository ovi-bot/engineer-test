export type AvgRow = {
    id: number;
    name: string;
    average_salary: number | null
};

export type EmployeeRow = {
    id: number;
    company_id: number;
    company_name: string;
    name: string;
    email: string;
    salary: number;
};
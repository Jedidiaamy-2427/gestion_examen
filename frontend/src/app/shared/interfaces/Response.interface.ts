export interface ResponseError {
    status: number;
    error: string;
}

export interface ResponseObjectData {
    "@context": string;
    "@id": string;
    "@type": string;
    member: any[];
    totalItems: number;
}
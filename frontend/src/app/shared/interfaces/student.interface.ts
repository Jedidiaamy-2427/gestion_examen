export interface Student {
  id: number;
  name: string;
  exams: string[]; // URLs des examens
}

export interface StudentResponseObject {
	"@context": string;
	"@id": string;
	"@type": string;
	id: number;
	name: string;
	exams: any[];
}
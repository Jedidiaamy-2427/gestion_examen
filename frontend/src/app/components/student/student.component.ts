import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { Student } from '../../shared/interfaces/student.interface';
import { StudentService } from '../../core/services/student.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-student',
  imports: [CommonModule],
  templateUrl: './student.component.html',
  styleUrl: './student.component.css'
})
export class StudentComponent {

constructor(private http: HttpClient,  private studentService: StudentService) {}

  page: number = 1;
  pageSize: number = 5;
  totalPages: number = 1;
  students: Student[] = [];


  ngOnInit(): void {
    this.studentService.getAll().subscribe({
      next: (students) => {
        const data = students.member;
        
        this.students = data.sort((a:any, b:any) => {
          if (a.exams.length > 0 && b.exams.length === 0) return -1; // a avant b
          if (a.exams.length === 0 && b.exams.length > 0) return 1;  // b avant a
          return 0; // conserver l'ordre relatif sinon
        });

        this.resetPagination()
      }
    });
  }

  resetPagination() {
    this.totalPages = Math.ceil(this.students.length / this.pageSize);

    if (this.page > this.totalPages) {
      this.page = this.totalPages || 1; // au moins 1 page
    }
  }

  get paginatedStudents(): Student[] {
    const students = this.students;

    if (!students || students.length === 0) return [];
    const start = (this.page - 1) * this.pageSize;
    const end = start + this.pageSize;
    return students.slice(start, end);
  }

  previousPage(): void {
    if (this.page > 1) this.page--;
  }

  nextPage(): void {
    if (this.page < this.totalPages) this.page++;
  }
}

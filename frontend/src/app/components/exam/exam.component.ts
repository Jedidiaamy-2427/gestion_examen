import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { ExamService } from '../../core/services/exam.service';
import { StudentService } from '../../core/services/student.service';

@Component({
  selector: 'app-exam',
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './exam.component.html',
  styleUrl: './exam.component.css'
})
export class ExamComponent {

  showModal = false;
  examForm!: FormGroup;
  today = new Date().toISOString().split('T')[0];
  isEditing = false;
  currentExamId: number | null = null;

  constructor(protected ExamService: ExamService,
              private fb: FormBuilder,
              protected studentService: StudentService){}

  ngOnInit() {
    this.ExamService.getAll().subscribe()

    this.studentService.getAll().subscribe();

    this.examForm = this.fb.group({
      student: ['', Validators.required],
      studentName: [''],
      location: ['', Validators.required],
      date: ['', Validators.required],
      time: ['', Validators.required],
      status: ['Confirmé', Validators.required],
    });
  } 

  openModal() {
    this.isEditing = false;
    this.currentExamId = null;
    this.examForm.reset({
      student: '',
      location: '',
      date: '',
      time: '',
      status: 'Confirmé'
    });
    this.showModal = true;
  }

   editExam(exam: any) {
    this.isEditing = true;
    this.currentExamId = exam.id;
    console.log('exam', exam)
    this.examForm.patchValue({
      student: exam.student,
      studentName: exam.studentName,
      location: exam.location,
      date: exam.date.split('T')[0],    
      time: exam.time.split('T')[1]?.substring(0,5) || '', 
      status: exam.status
    });

    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  submitExam() {
    if (this.examForm.valid) {
      const examData = this.examForm.value;

      if (this.isEditing && this.currentExamId) {

        this.ExamService.updateExam(this.currentExamId, examData).subscribe({
          next: () => {
            this.closeModal();
            this.ExamService.getAll().subscribe();
          },
          error: err => console.error("Erreur mise à jour examen", err)
        });
      } else {
 
        this.ExamService.addExam(examData).subscribe({
          next: () => {
            this.closeModal();
            this.ExamService.getAll().subscribe(); 
          },
          error: err => console.error("Erreur création examen", err)
        });
      }
    }
  }

    deleteExam(id: number) {
    if (confirm("Voulez-vous vraiment supprimer cet examen ?")) {
      this.ExamService.removeExam(id).subscribe({
        next: () => this.ExamService.getAll().subscribe(),
        error: err => console.error("Erreur suppression examen", err)
      });
    }
  }
} 


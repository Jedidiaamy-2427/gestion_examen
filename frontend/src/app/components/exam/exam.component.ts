import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { ExamService } from '../../core/services/exam.service';

@Component({
  selector: 'app-exam',
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './exam.component.html',
  styleUrl: './exam.component.css'
})
export class ExamComponent {

  constructor(protected ExamService: ExamService, private router: Router){}

  ngOnInit() {
    this.ExamService.getAll().subscribe()
  }

}

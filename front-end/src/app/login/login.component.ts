import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  form!: FormGroup;
  errors: any;

  constructor(
    private formBuilder: FormBuilder,
    private http: HttpClient,
    private router: Router,
  ) { }

  ngOnInit(): void {
    this.form = this.formBuilder.group({
      username: '',
      password: ['', [ Validators.required, Validators.pattern('(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}')]],
    })
  }

  submit():void {
    this.http.post('http://localhost:8000/api/login_check', this.form.getRawValue(), { withCredentials: true }).subscribe(
      (res: any) => {
        localStorage.setItem('token', res.token);
      },
      (error: HttpErrorResponse) => {
        console.log(error);
        this.errors = error.error.message;
      },
      () => {
        this.router.navigate(['/'])
      });
  }

}

import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {
  form!: FormGroup;
  errors: any;

  roles = [
    { id: "ROLE_SUPPLIER", name: "Supplier" },
    { id: "ROLE_DEVELOPER", name: "Developer" },
    { id: "ROLE_MANAGER", name: "Manager" },
    { id: "ROLE_ADMINISTRATOR", name: "Administrator" },
  ];

  constructor(
    private formBuilder: FormBuilder,
    private http: HttpClient,
    private router: Router,
    ) {
    
  }

  ngOnInit(): void {
    this.form = this.formBuilder.group({
      username: '',
      name: '',
      email: '',
      password: '',
      retypedPassword: '',
      roles: [],
    })
  }

  submit() {

    let options = { headers: new HttpHeaders({
      'Access-Control-Allow-Origin':"*",
      })
    };

    this.http.post('http://localhost:8000/api/users', this.form.getRawValue(), options).subscribe(
      res => console.log(res),
      (error: HttpErrorResponse) => {
        this.errors = error.error.violations;
      }, 
      () => {
        this.errors = 'Please check your email to confirm your account.';
        this.router.navigate(['/login'])
      })
  }
}

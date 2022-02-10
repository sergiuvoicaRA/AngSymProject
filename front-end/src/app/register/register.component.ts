import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {
  form!: FormGroup;
  errors: any;
  params: any;
  alreadyPartner: any;
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
    private route: ActivatedRoute,
    ) {

  }

  ngOnInit(): void {
    this.route.queryParams.subscribe(params => this.params = params)
    this.alreadyPartner = this.params.hasOwnProperty('user_email')
    if (this.alreadyPartner) {
      
      this.form = this.formBuilder.group({
        username: '',
        name: this.params.name,
        email: this.params.user_email,
        password: '',
        retypedPassword: '',
        roles: [[this.params.roles]],
        confirmationToken: this.params.confirmationToken,
      })
    } else {
      this.form = this.formBuilder.group({
        username: '',
        name: '',
        email: '',
        password: '',
        retypedPassword: '',
        roles: [],
      })
    }
    console.log(this.form);
  }

  submit() {
    let options = { headers: new HttpHeaders({
      'Access-Control-Allow-Origin':"http://localhost:4200",
      })
    };
    if (this.alreadyPartner) {
      this.http.post(`http://localhost:8000/api/users/${this.form.getRawValue().id}`, this.form.getRawValue(), options).subscribe(
        (res) => {
        },
        (err) => console.log(err),
        () => {
          this.errors = 'Please check your email to confirm your account';
          this.router.navigate(['/login']);
        }
      )
    } else {
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
}

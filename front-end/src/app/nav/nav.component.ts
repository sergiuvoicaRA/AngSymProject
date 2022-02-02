import { Component, EventEmitter, OnInit } from '@angular/core';
import { Emitters } from '../emitters/emitters';

@Component({
  selector: 'app-nav',
  templateUrl: './nav.component.html',
  styleUrls: ['./nav.component.css']
})
export class NavComponent implements OnInit {
  authenticated = false;
  constructor() { }

  ngOnInit(): void {
    Emitters.authEmitter.subscribe(
      (auth: boolean) => {
        this.authenticated = auth;
      }
    )
  }

  logout(): void {
    this.authenticated = false;
    localStorage.removeItem('token');
  }

}

import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { RegisterComponent } from './register/register.component';
import { LoginComponent } from './login/login.component';
import { HomeComponent } from './home/home.component';
import { NavComponent } from './nav/nav.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { AgGridModule } from 'ag-grid-angular';
import { BtnCellRenderer } from './button-cell-renderer.component';
import { NgbdModalBasicModule } from './modal-basic/modal-basic.module';
import { StatusRenderer } from './status-renderer-component';

@NgModule({
  declarations: [
    AppComponent,
    RegisterComponent,
    LoginComponent,
    HomeComponent,
    NavComponent,
    BtnCellRenderer,
    StatusRenderer
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    AgGridModule.withComponents([BtnCellRenderer, StatusRenderer]),
    NgbdModalBasicModule,
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }

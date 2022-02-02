import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { BrowserModule } from '@angular/platform-browser';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { NgbdModalBasic } from './modal-basic';

@NgModule({
  imports: [BrowserModule, NgbModule, FormsModule, ReactiveFormsModule],
  declarations: [NgbdModalBasic],
  exports: [NgbdModalBasic],
  bootstrap: [NgbdModalBasic]
})
export class NgbdModalBasicModule {}

import {Component, OnInit} from '@angular/core';
import { Output, EventEmitter } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import {NgbModal, ModalDismissReasons} from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'ngbd-modal-basic',
  templateUrl: './modal-basic.html'
})
export class NgbdModalBasic implements OnInit {
  closeResult = '';
  form!: FormGroup;

  @Output() newItemEvent = new EventEmitter<object>();

  constructor(
    private modalService: NgbModal, 
    private formBuilder: FormBuilder,
    ) {}

  ngOnInit(): any {
    this.form = this.formBuilder.group({
      name: '',
      email: '',
      personalInfo: '',
      role: '',
      organization: '',
      status: '',
    })
  }

  open(content: any) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title'}).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }

  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return `with: ${reason}`;
    }
  }

  submit() {
    this.newItemEvent.emit(this.form.getRawValue());
  }
}

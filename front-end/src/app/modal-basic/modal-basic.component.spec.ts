import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NgbdModalBasic } from './modal-basic';

describe('ModalBasicComponent', () => {
  let component: NgbdModalBasic;
  let fixture: ComponentFixture<NgbdModalBasic>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NgbdModalBasic ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NgbdModalBasic);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

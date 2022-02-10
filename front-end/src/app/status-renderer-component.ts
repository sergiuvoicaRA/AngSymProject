import { Component } from '@angular/core';
import { ICellRendererParams } from 'ag-grid-community';
import { ICellRendererAngularComp } from 'ag-grid-angular';

@Component({
  selector: 'app-status-renderer',
  template: `<span> {{ value }}</span>`,
})
export class StatusRenderer implements ICellRendererAngularComp {
  refresh(params: ICellRendererParams): boolean {
      return true;
  }
  value: any;

  agInit(params: ICellRendererParams): void {
    this.value = params.value;
  }
}

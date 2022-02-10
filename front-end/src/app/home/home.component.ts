import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Component, OnInit, ViewChild } from '@angular/core';
import { JwtHelperService } from '@auth0/angular-jwt';
import { AgGridAngular } from 'ag-grid-angular';
import { BtnCellRenderer } from '../button-cell-renderer.component';

import { Emitters } from '../emitters/emitters';
import { NgbdModalBasic } from '../modal-basic/modal-basic';
import { StatusRenderer } from '../status-renderer-component';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  message = "";
  role = "";
  token!: any;
  frameworkComponents: any;
  rowDataClicked1 = {};
  rowHeight = 35;
  rowSelection = 'multiple';
  modal: any;

  @ViewChild('agGrid')
  agGrid!: AgGridAngular;
  api: any;

  constructor(
    private http: HttpClient,
  ) {
    this.frameworkComponents = {
      buttonRenderer: BtnCellRenderer,
      statusRenderer: StatusRenderer,
    }
    this.modal = { NgbdModalBasic: NgbdModalBasic}
  }

  columnDefs = [
    { headerName: "Index", valueGetter: "node.rowIndex + 1", width: 60 },
    { headerName: "Id", field:"id", sortable: true, filter: true, width: 80, hide: true},
    { headerName: "Name", field: "name", sortable: true, filter: true, editable: true},
    { headerName: "Email", field: "email", sortable: true, filter: true, width: 250, editable: true },
    { headerName: "Personal Info", field: "personalInfo", sortable: true, filter: true, editable: true },
    { 
      headerName: "Role", 
      field: "roles", 
      sortable: true, 
      filter: true, 
      width: 140, 
      editable: true,
      cellRenderer: 'statusRenderer',
      cellEditor: 'agSelectCellEditor',
      cellEditorParams: {
        cellRenderer: 'statusRenderer',
        values: ['ADMINISTRATOR', 'MANAGER', 'DEVELOPER', 'SUPPLIER'],
      },
    },
    { headerName: "Organization", field: "organization", sortable: true, filter: true, width: 250, editable: true },
    { 
      headerName: "Status", 
      field: "status", 
      sortable: true, 
      filter: true, 
      width: 80, 
      editable: true, 
      flex: 1,
      cellRenderer: 'statusRenderer',
      cellEditor: 'agSelectCellEditor',
      cellEditorParams: {
        cellRenderer: 'statusRenderer',
        values: ['ACTIVE', 'INACTIVE'],
      },
    },
  ];
  
  rowData: any;

  ngOnInit(): void {
    this.token = localStorage.getItem('token');
    let headers = new HttpHeaders({
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${this.token}`
    });

    const helper = new JwtHelperService();
    const userinfo = helper.decodeToken(this.token);

    this.http.get(`http://localhost:8000/user-details/${userinfo.username}`, { headers: headers }).subscribe(
      (res: any) => {
        this.role = res.user_data.role[0];
        if (this.role === 'ROLE_ADMINISTRATOR') {
          this.message = `Hi ${res.user_data.name}, You are logged in as an ${res.user_data.role[0].replace('ROLE_', '').toLowerCase()}! Feel free to add users to your organization. Create custom roles such as: Manager, Developer, Supplier.`
          
          this.updateTable();

          console.log(this.rowData);
        } else if (this.role === 'ROLE_MANAGER') {
          this.message = 'Logged in as manager';
        } else if (this.role === 'ROLE_DEVELOPER') {
          this.message = 'Logged in as developer';
        } else {
          this.message = `Hi ${res.user_data.name}, You are logged in as ${res.user_data.role[0].replace('ROLE_', '').toLowerCase()}! Here you will receive daily tasks`;
        }


        Emitters.authEmitter.emit(true);
      },
      err => {
        this.message = "You are not logged in!";

        Emitters.authEmitter.emit(false);
      }
    )
  }

  onCellValueChanged(event: any) {
    if(event.data.status === 'ACTIVE') {
      console.log(event);
    }

    this.editPartner(event.data);
  }

  addItem(newItem: any) {
    newItem.username = `Username${Math.floor(Math.random() * (9999999 - 1000000) + 1000000)}`;
    newItem.password = 'Password123';
    newItem.retypedPassword = newItem.password;
    this.http.post('http://localhost:8000/api/users', newItem).subscribe((res) => {
      this.updateTable()
  });
  }

  editPartner(data: any) {
    this.http.put(`http://localhost:8000/dashboard/edit/${data.id}`, data).subscribe(
      (res: any) => {
        console.log(res);
      },
      (err) => console.log(err)
    );
  }

  deletePartner() {
    if (confirm("Are you sure you want to delete the selected partner(s)")) {
      const selectedRows = this.api.getSelectedRows();
      this.api.applyTransaction({ remove: selectedRows });
      selectedRows.forEach((element: any) => {
        this.rowData.splice(this.rowData.findIndex((i: any) => {
          return i.id === element.id;
        }), 1);

        this.http.delete(`http://localhost:8000/api/users/${element.id}`)
          .subscribe(() => this.updateTable())
      });
    }
  }

  updateTable() {
    let options = { headers: new HttpHeaders({
      'Authorization':`Bearer ${this.token}`,
      })
    };

    this.http.get('http://localhost:8000/api/users', options).subscribe(
      (res: any)=> {
        // res['hydra:member'].forEach((e: any) => {
        //   e.roles = e.roles.replace('ROLE_', '').toUpperCase();
        // });
        this.rowData = res['hydra:member'];
      }
    );
  }

  onGridReady(params: any) {
    console.log(params);
    this.api =params.api;
  }
}

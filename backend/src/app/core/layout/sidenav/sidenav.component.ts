import { Component, OnInit, ViewEncapsulation } from '@angular/core';

@Component({
    selector    : 'layout-sidenav',
    templateUrl : './sidenav.component.html',
    styleUrls   : [ './sidenav.component.scss' ],
})
export class SidenavComponent implements OnInit {

    categories = [ { name: 'create' }, { name: 'see all', link : '/categories' }, ];
    posts      = [ { name: 'create' }, { name: 'see all' }, ];

    constructor () {
    }

    ngOnInit () {
    }

}

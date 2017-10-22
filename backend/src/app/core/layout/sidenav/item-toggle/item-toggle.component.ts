import { Component, Input } from '@angular/core';

@Component({
    selector    : 'sidenav-item-toggle',
    templateUrl : './item-toggle.component.html',
    styleUrls   : [ './item-toggle.component.scss' ],
})
export class ItemToggleComponent {
    //  always collapsed by default
    isCollapsed = true;

    //  input for components
    @Input('icon') icon: string;
    @Input('name') name: string;
    @Input('children') children: any[];

    constructor () { }

}

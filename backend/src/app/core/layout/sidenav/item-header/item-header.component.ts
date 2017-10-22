import { Component, Input } from '@angular/core';

@Component({
    selector    : 'sidenav-item-header',
    templateUrl : './item-header.component.html',
    styleUrls   : [ './item-header.component.scss', '../sidenav.component.scss' ],
})
export class ItemHeaderComponent {

    @Input('name') name: string;

    constructor () {
    }

}

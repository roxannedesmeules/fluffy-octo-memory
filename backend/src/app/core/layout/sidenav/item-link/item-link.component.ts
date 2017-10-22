import { Component, Input } from '@angular/core';

@Component({
    selector    : 'sidenav-item-link',
    templateUrl : './item-link.component.html',
    styleUrls   : [ './item-link.component.scss' ],
})
export class ItemLinkComponent {

    @Input('icon') icon: string;
    @Input('name') name: string;
    @Input('link') link: string;

    constructor () {}

}

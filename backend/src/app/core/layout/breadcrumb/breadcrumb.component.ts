import { Component, Input } from '@angular/core';
import { BreadcrumbItems } from './breadcrumb-items.model';

@Component({
    selector    : 'layout-breadcrumb',
    templateUrl : './breadcrumb.component.html',
    styleUrls   : [ './breadcrumb.component.scss' ],
})
export class BreadcrumbComponent {

    @Input('breadcrumb') breadcrumb: BreadcrumbItems[];

    constructor () { }
}

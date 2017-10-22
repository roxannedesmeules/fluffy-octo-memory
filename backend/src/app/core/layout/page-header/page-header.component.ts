import { Component, OnDestroy } from '@angular/core';
import { BreadcrumbItems } from '../breadcrumb/breadcrumb-items.model';
import { PageHeaderService } from './page-header.service';
import { Subscription } from 'rxjs/Subscription';

@Component({
    selector    : 'layout-page-header',
    templateUrl : './page-header.component.html',
    styleUrls   : [ './page-header.component.scss' ],
})
export class PageHeaderComponent implements OnDestroy {
    subscription: Subscription;
    header;

    title: string;
    subTitle: string;
    breadcrumb: BreadcrumbItems[] = null;

    constructor ( private headerService: PageHeaderService ) {
        this.subscription = this.headerService.getPageHeader().subscribe((header) => { this.header = header });
    }

    ngOnDestroy () {}
}

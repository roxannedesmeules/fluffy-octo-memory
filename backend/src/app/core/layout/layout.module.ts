import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { RouterModule } from '@angular/router';

//  Components
import { TopnavComponent } from './topnav/topnav.component';
import { SidenavComponent } from './sidenav/sidenav.component';
import { ItemToggleComponent } from './sidenav/item-toggle/item-toggle.component';
import { ItemHeaderComponent } from './sidenav/item-header/item-header.component';
import { ItemLinkComponent } from './sidenav/item-link/item-link.component';
import { AppContentComponent } from './app-content/app-content.component';
import { PageHeaderComponent } from './page-header/page-header.component';
import { BreadcrumbComponent } from './breadcrumb/breadcrumb.component';

//  Services
import { PageHeaderService } from './page-header/page-header.service';

@NgModule({
    imports      : [
        CommonModule,
        NgbModule,
        RouterModule,
    ],
    declarations : [
        TopnavComponent,
        SidenavComponent,
        ItemToggleComponent,
        ItemHeaderComponent,
        ItemLinkComponent,
        AppContentComponent,
        PageHeaderComponent,
        BreadcrumbComponent,
    ],
    providers : [
        PageHeaderService,
    ]
})
export class LayoutModule {
}

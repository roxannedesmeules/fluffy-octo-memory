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
import { RegularAdminPageComponent } from './regular-admin-page/regular-admin-page.component';
import { PageHeaderComponent } from './page-header/page-header.component';
import { BreadcrumbComponent } from './breadcrumb/breadcrumb.component';

//  Services
import { PageHeaderService } from './page-header/page-header.service';
import { CustomAdminPageComponent } from './custom-admin-page/custom-admin-page.component';

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
        RegularAdminPageComponent,
        PageHeaderComponent,
        BreadcrumbComponent,
        CustomAdminPageComponent,
    ],
    providers : [
        PageHeaderService,
    ]
})
export class LayoutModule {
}

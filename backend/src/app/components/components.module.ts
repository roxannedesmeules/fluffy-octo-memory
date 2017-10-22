import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DashboardModule } from './dashboard/dashboard.module';

//  Components
import { CategoriesComponent } from './categories/categories.component';

@NgModule({
    imports      : [
        CommonModule,
        DashboardModule,
    ],
    declarations : [
        CategoriesComponent
    ],
})
export class ComponentsModule {
}

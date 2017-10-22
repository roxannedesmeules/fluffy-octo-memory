import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CategoriesComponent } from './components/categories/categories.component';
import { AppContentComponent } from './core/layout/app-content/app-content.component';

const routes: Routes = [
    {
        path        : '',
        component : AppContentComponent,
        children  : [ { path : 'categories', component : CategoriesComponent } ],
    },
];

@NgModule({
    imports : [ RouterModule.forRoot(routes) ],
    exports : [ RouterModule ],
})
export class AppRoutingModule {
}

import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

//   Guards
import { AuthGuard } from "../../shared/helpers/guards/auth.guard";

//   Components
import { RegularAdminPageComponent } from "core/layout/regular-admin-page/regular-admin-page.component";
import { ListComponent } from "components/categories/list/list.component";
import { DetailsComponent } from "components/categories/details/details.component";

//  Resolver
import { ListResolve } from "components/categories/list/list.resolve";
import { DetailsResolve } from "components/categories/details/details.resolve";
import { LanguageResolve } from "../../shared/helpers/resolve/language.resolve";

const routes: Routes = [
	{
		path        : "",
		component   : RegularAdminPageComponent,
		canActivate : [ AuthGuard ],
		children    : [
			{ path : "categories", component : ListComponent, resolve : { list : ListResolve }, },
			{ path : "categories/create", component : DetailsComponent, resolve : { languages : LanguageResolve } },
		],
	},
];

@NgModule({
	imports   : [ RouterModule.forChild(routes) ],
	exports   : [ RouterModule ],
	providers : [
		ListResolve,
		DetailsResolve,
		LanguageResolve,
	]
})
export class CategoriesRoutingModule {}

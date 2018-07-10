import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { CommunicationComponent } from "./communication.component";
import { DetailComponent } from "admin/communication/detail/detail.component";
import { ListComponent } from "admin/communication/list/list.component";

import { DetailResolve, FullListResolve } from "@core/data/communication";

const routes: Routes = [
	{
		path      : "",
		component : CommunicationComponent,
		children  : [
			{
				path      : "",
				component : ListComponent,
				resolve   : {
					messages : FullListResolve,
				},
			}, {
				path      : ":id",
				component : DetailComponent,
				resolve   : {
					message : DetailResolve,
				},
			}
		],
	},
];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class CommunicationRoutingModule {
}

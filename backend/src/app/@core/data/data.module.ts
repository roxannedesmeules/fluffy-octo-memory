import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { SERVICES as CATEGORY_SERVICES } from "@core/data/categories";
import { SERVICES as COMMUNICATION_SERVICES } from "@core/data/communication";
import { SERVICES as LANGUAGE_SERVICES } from "@core/data/languages";
import { SERVICES as POST_SERVICES } from "@core/data/posts";
import { SERVICES as TAG_SERVICES } from "@core/data/tags";
import { SERVICES as USER_SERVICES } from "@core/data/users";


const SERVICES = [
	...CATEGORY_SERVICES,
	...COMMUNICATION_SERVICES,
	...LANGUAGE_SERVICES,
	...POST_SERVICES,
	...TAG_SERVICES,
	...USER_SERVICES,
];

@NgModule({
	imports   : [
		CommonModule,
	],
	providers : [
		...SERVICES
	],
})
export class DataModule {
	static forRoot (): ModuleWithProviders {
		return <ModuleWithProviders>{
			ngModule  : DataModule,
			providers : [
				...SERVICES,
			],
		};
	}
}

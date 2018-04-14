import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { PostRoutingModule } from "./post-routing.module";

import { TagsInputModule } from "ngx-tags-input";
import { FroalaEditorModule, FroalaViewModule } from 'angular-froala-wysiwyg';
import { PaginationModule } from "@shared/pagination/pagination.module";
import { PipesModule } from "@shared/pipes/pipes.module";
import { ThemeModule } from "@theme/theme.module";

import { PostComponent } from "admin/post/post.component";
import { ListComponent } from "admin/post/list/list.component";
import { DetailComponent } from "admin/post/detail/detail.component";


@NgModule({
	imports      : [
		CommonModule,
		FormsModule,
		ReactiveFormsModule,
		PostRoutingModule,

		TagsInputModule,
		FroalaEditorModule,
		FroalaViewModule,

		PaginationModule,
		PipesModule,
		ThemeModule,
	],
	declarations : [
		PostComponent,
		ListComponent,
		DetailComponent,
	],
})
export class PostModule {
}

import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { PostRoutingModule } from "./post-routing.module";

import { FroalaEditorModule, FroalaViewModule } from 'angular-froala-wysiwyg';
import { NgSelectModule } from "@ng-select/ng-select";
import { PaginationModule } from "@shared/pagination/pagination.module";
import { PipesModule } from "@shared/pipes/pipes.module";
import { ThemeModule } from "@theme/theme.module";
import { FileInputModule } from "@shared/file-input/file-input.module";

import { PostComponent } from "admin/post/post.component";
import { ListComponent } from "admin/post/list/list.component";
import { DetailComponent } from "admin/post/detail/detail.component";
import { CommentComponent } from "admin/post/comment/comment.component";
import { SingleComponent } from './comment/single/single.component';


@NgModule({
	imports      : [
		CommonModule,
		FormsModule,
		ReactiveFormsModule,
		PostRoutingModule,

		FroalaEditorModule,
		FroalaViewModule,
		NgSelectModule,

		FileInputModule,
		PaginationModule,
		PipesModule,
		ThemeModule,
	],
	declarations : [
		PostComponent,
		ListComponent,
		DetailComponent,
		CommentComponent,
		SingleComponent,
	],
})
export class PostModule {
}

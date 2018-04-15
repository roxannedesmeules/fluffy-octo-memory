import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { FileInputComponent } from "@shared/file-input/file-input.component";

@NgModule({
	imports      : [
		CommonModule,
		FormsModule,
		ReactiveFormsModule,
	],
	declarations : [ FileInputComponent ],
	exports      : [ FileInputComponent ],
})
export class FileInputModule {
}

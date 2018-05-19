import { Component, OnInit } from "@angular/core";
import { ErrorResponse } from "@core/data/error-response.model";
import { Tag, TagService } from "@core/data/tags";

@Component({
	selector    : "app-widget-top-tags",
	templateUrl : "./tags.component.html",
	styleUrls   : [ "./tags.component.scss" ],
})
export class TagsComponent implements OnInit {

	public tags: Tag[];

	constructor (public _tagService: TagService ) {
	}

	ngOnInit () {
		this.getTags();
	}

	public getTags () {
		this._tagService
				.findAll()
				.subscribe(
						(result: Tag[]) => {
							this.tags = result;
						},
						(err: ErrorResponse) => { console.log(err); }
				);
	}
}
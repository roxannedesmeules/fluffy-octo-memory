import { Component, Input, OnInit } from "@angular/core";

@Component({
	selector    : "app-blog-tags",
	templateUrl : "./tags.component.html",
	styleUrls   : [ "./tags.component.scss" ],
})
export class TagsComponent implements OnInit {

	@Input() tags: any[];

	constructor () {
	}

	ngOnInit () {
	}

}

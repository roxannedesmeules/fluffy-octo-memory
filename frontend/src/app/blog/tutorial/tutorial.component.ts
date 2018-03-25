import { Component, OnInit } from "@angular/core";

@Component({
	selector    : "app-blog-tutorial",
	templateUrl : "./tutorial.component.html",
	styleUrls   : [ "./tutorial.component.scss" ],
})
export class TutorialComponent implements OnInit {

	public tags = [
		"Business", "Economy", "Finance",
	];

	constructor () {
	}

	ngOnInit () {
	}

}

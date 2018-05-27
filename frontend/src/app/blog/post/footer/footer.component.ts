import { Component, Input, OnInit } from "@angular/core";
import { Author } from "@core/data/users";

@Component({
	selector    : "app-blog-post-footer",
	templateUrl : "./footer.component.html",
	styleUrls   : [ "./footer.component.scss" ],
})
export class FooterComponent implements OnInit {

	@Input() author: Author;

	constructor () {
	}

	ngOnInit () {
	}

}

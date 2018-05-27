import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Post } from "@core/data/posts";

@Component({
	selector    : "app-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {

	public list: Post[];

	constructor ( private route: ActivatedRoute ) {
	}

	ngOnInit () {
		this.list = this.route.snapshot.data[ "posts" ];
	}
}

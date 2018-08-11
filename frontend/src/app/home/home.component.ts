import { Component, OnInit, TemplateRef, ViewChild } from "@angular/core";
import { Meta, Title } from "@angular/platform-browser";
import { Post, PostService } from "@core/data/posts";

@Component({
	selector    : "app-home",
	templateUrl : "./home.component.html",
	styleUrls   : [ "./home.component.scss" ],
})
export class HomeComponent implements OnInit {

	public latest: Post[] = [];
	public featured: Post[] = [];

	@ViewChild('metadataTranslation') metadataTranslation: TemplateRef<any>;

	constructor (private title: Title,
				 private meta: Meta,
				 private postService: PostService) {
	}

	ngOnInit () {
		this.setMetadata();
		this.getFeaturedPost();
		this.getLatestPosts();
	}

	private getLatestPosts () {
		this.latest = [ new Post(), new Post(), new Post() ];

		this.postService
			.latests()
			.subscribe((result: Post[]) => { this.latest = result; });
	}

	private getFeaturedPost () {
		this.postService
			.featured()
			.subscribe((result: Post[]) => { this.featured = result; });
	}

	public scroll ( target ) {
		target.scrollIntoView({ behavior : "smooth" });
	}

	private setMetadata() {
		this.title.setTitle("ladydev.io");

		const nodes = this.metadataTranslation.createEmbeddedView({}).rootNodes

		this.meta.updateTag({ name: "description", content: nodes[1].innerText }, "name='description'");
	}
}

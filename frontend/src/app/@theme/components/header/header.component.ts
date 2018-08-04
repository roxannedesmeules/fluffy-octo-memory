import { Component, HostListener, Inject, LOCALE_ID, OnInit } from "@angular/core";
import { animate, state, style, transition, trigger } from "@angular/animations";
import { NavigationStart, Router } from "@angular/router";

@Component({
	selector    : "app-layout-header",
	templateUrl : "./header.component.html",
	styleUrls   : [ "./header.component.scss" ],
	animations  : [
		trigger("visibilityChanged", [
			state("open", style({ opacity : 1, display : "block", 'z-index' : 9999 })),
			state("close", style({ opacity : 0, display : "none" })),
			transition("open => close", animate("600ms ease-out")),
			transition("close => open", animate("300ms ease-in")),
		]),

		trigger("collapseMenu", [
			state("open",  style({ opacity : 1, display : "block", transform : "translate3d(0, 0, 0)" })),
			state("close", style({ opacity : 0, display : "none", transform : "translate3d(0, -100%, 0)" })),
			transition("* => desktop", animate("200ms ease-in")),
			transition("desktop => *", animate("200ms ease-out")),
			transition("close => open", animate("600ms ease-in")),
			transition("open => close", animate("100ms ease-out")),
		]),

		trigger("collapse", [
			transition("close => open", animate("600ms ease-in")),
			transition("open => close", animate("100ms ease-out")),
		]),
	],
})
export class HeaderComponent implements OnInit {

	public exactRoute: any = { exact: true };
	public blogParams: any = { page : 0, "per-page" : 10 };

	public menuState: string = "";

	public searchValue: string = "";
	public searchState: string = "close";

	@HostListener('window:resize', ['$event'])
	onResize(event) {
		this.resizeMenu(event.target.innerWidth);
	}

	constructor ( @Inject(LOCALE_ID) protected localeId: string,
				  private router: Router) {
	}

	ngOnInit () {
		this.resizeMenu(window.innerWidth);

		this.router.events
			.subscribe((event) => {
				if (event instanceof NavigationStart) {
					this.toggleMenu();
				}
			});
	}

	public isCurrent ( lang: string ): boolean {
		return (this.localeId === lang);
	}

	public isOpen (): boolean {
		return (this.menuState === "open");
	}

	private resizeMenu (size) {
		if (size >= 992) {
			this.menuState = "desktop";
		} else {
			this.menuState = "close";
		}
	}

	public toggleMenu () {
		switch (this.menuState) {
			case "open":
				this.menuState = "close";
				break;

			case "close":
				this.menuState = "open";
				break;
		}
	}

	public toggleSearch ( action: string ) {
		switch (action) {
			case "open":
				this.searchState = "open";
				break;

			case "close":
				this.searchState = "close";
				break;
		}

		this.searchValue = "";
	}

	public searchFor (event = null) {
		if (event === null || event.key === "Enter") {
			const params = {
				queryParams : {
					"page"     : 0,
					"per-page" : 10,
				},
			};

			if (this.searchValue !== "") {
				params.queryParams[ "search" ] = this.searchValue;
			}

			//  navigate to the blog list with the search value as query param
			this.router.navigate([ "/blog" ], params);

			//  reset the searched value
			this.toggleSearch('close');
		}
	}
}

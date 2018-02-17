import { Component, Input, OnInit } from "@angular/core";
import { NbMenuService, NbSidebarService } from "@nebular/theme";

import { UserService } from "@core/data/users/user.service";
import { AnalyticsService } from "@core/utils/analytics.service";
import { User } from "@core/data/users/user.model";

@Component({
	selector    : "ngx-header",
	styleUrls   : [ "./header.component.scss" ],
	templateUrl : "./header.component.html",
})
export class HeaderComponent implements OnInit {

	@Input() position = "normal";

	public user: any;
	public userMenu = [ { title : "Profile", url : "/admin/user/me/profile" }, { title : "Log out" } ];

	constructor ( private sidebarService: NbSidebarService,
				  private menuService: NbMenuService,
				  private userService: UserService,
				  private analyticsService: AnalyticsService ) {
	}

	ngOnInit () {
		this.user = new User(this.userService.getAppUser());
	}

	toggleSidebar (): boolean {
		this.sidebarService.toggle(true, "menu-sidebar");
		return false;
	}

	goToHome () {
		this.menuService.navigateHome();
	}

	startSearch () {
		this.analyticsService.trackEvent("startSearch");
	}
}

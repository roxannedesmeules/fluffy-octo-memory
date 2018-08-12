import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { PipesModule } from "@shared/pipes/pipes.module";
import { PostSummaryModule } from "@shared/post-summary/post-summary.module";
import { DirectivesModule } from "@shared/directives/directives.module";

import { HomeRoutingModule } from "./home-routing.module";
import { HomeComponent } from "./home.component";
import { FeaturedPostComponent } from "./featured-post/featured-post.component";

@NgModule({
    imports      : [
        CommonModule,
        HomeRoutingModule,

        DirectivesModule,
        PipesModule,
        PostSummaryModule,
    ],
    declarations : [
        HomeComponent,
        FeaturedPostComponent,
    ],
})
export class HomeModule {
}

import { inject, TestBed } from "@angular/core/testing";

import { CategoryPostService } from "./category-post.service";

describe("CategoryPostService", () => {
    beforeEach(() => {
        TestBed.configureTestingModule({
            providers : [ CategoryPostService ],
        });
    });

    it("should be created", inject([ CategoryPostService ], (service: CategoryPostService) => {
        expect(service).toBeTruthy();
    }));
});

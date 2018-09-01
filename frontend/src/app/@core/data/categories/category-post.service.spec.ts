import { HttpClientTestingModule, HttpTestingController } from "@angular/common/http/testing";
import { TestBed } from "@angular/core/testing";
import { CategoryCount } from "@core/data/categories/category-count.model";
import { ErrorResponse } from "@core/data/error-response.model";

import { CategoryPostService } from "./category-post.service";

describe("CategoryPostService", () => {
    let service: CategoryPostService;
    let httpMock: HttpTestingController;

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports   : [ HttpClientTestingModule ],
            providers : [ CategoryPostService ],
        });

        service  = TestBed.get(CategoryPostService);
        httpMock = TestBed.get(HttpTestingController);
    });

    afterEach(() => {
        httpMock.verify();
    });

    it("should be created", () => {
        expect(service).toBeTruthy();
    });

    describe("#findAll", () => {
        it("should return an Observable<CategoryCount[]>", () => {
            const data = [
                { id : 1, count : 3 },
                { id : 2, count : 1 },
                { id : 3, count : 5 },
            ];

            service.findAll().subscribe(result => {
                expect(result.length).toBe(3);
                expect(result).toEqual(data);
            });

            const req = httpMock.expectOne("categories/posts/count");

            expect(req.request.method).toBe("GET");
            req.flush(data);
        });
    });

    describe("#findOne", () => {
        it("should return a 501 error", () => {
            service.findOne().subscribe(
                    () => {
                    },
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(501);
                    });
        });
    });

    describe("#findById", () => {
        it("should return a 501 error", () => {
            service.findById().subscribe(
                    () => {
                    },
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(501);
                    });
        });
    });

    describe("#mapListToModelList", () => {
        it("should return an empty list, if there is no object passed", () => {
            const models = service.mapListToModelList([]);

            expect(models.length).toEqual(0);
        });

        it("should map a list of objects to a list of CategoryCount models", () => {
            const data = [
                { id : 1, count : 3 },
                { id : 2, count : 1 },
                { id : 3, count : 5 },
            ];

            const models = service.mapListToModelList(data);

            expect(models.length).toEqual(data.length);
            expect(models).toEqual(jasmine.arrayContaining(data));
        });
    });

    describe("#mapModel", () => {
        it("should map a single object to a CategoryCount model", () => {
            const data  = { id : 1, count : 3 };
            const model = service.mapModel(data);

            expect(model).toEqual(jasmine.any(CategoryCount));
            expect(model.id).toEqual(data.id);
            expect(model.count).toEqual(data.count);
        });
    });

    describe("#url", () => {
        it("should transform :baseUrl/:modelName/count into categories/posts/count", () => {
            const expected = "categories/posts/count";

            expect(service.url(null, ":baseUrl/:modelName/count")).toEqual(expected);
        });
    });
});

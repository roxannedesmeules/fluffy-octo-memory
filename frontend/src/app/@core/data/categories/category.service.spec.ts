import { HttpClientTestingModule, HttpTestingController } from "@angular/common/http/testing";
import { TestBed } from "@angular/core/testing";
import { Category } from "@core/data/categories/category.model";
import { ErrorResponse } from "@core/data/error-response.model";

import { CategoryService } from "./category.service";

describe("CategoryService", () => {
    let service: CategoryService;
    let httpMock: HttpTestingController;

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports   : [ HttpClientTestingModule ],
            providers : [ CategoryService ],
        });

        service  = TestBed.get(CategoryService);
        httpMock = TestBed.get(HttpTestingController);
    });

    afterEach(() => {
        httpMock.verify();
    });

    it("should be created", () => {
        expect(service).toBeTruthy();
    });

    describe("#findAll", () => {
        it("should return an Observable<Category[]>", () => {
            const data = [
                { id : 1, name : "Test 1", slug : "test-1" },
                { id : 2, name : "Test 2", slug : "test-2" },
                { id : 3, name : "Test 3", slug : "test-3" },
            ];

            service.findAll().subscribe(result => {
                expect(result.length).toBe(3);
                expect(result).toEqual(jasmine.arrayContaining(data));
                expect(result).toEqual(jasmine.arrayContaining([ jasmine.any(Category) ]));
            });

            const req = httpMock.expectOne("categories");

            expect(req.request.method).toBe("GET");
            req.flush(data);
        });
    });

    describe("#findById", () => {
        it("should return an Observable<Category>", () => {
            const data = { id : 1, name : "Test 1", slug : "test-1" };

            service.findById(data.id).subscribe(result => {
                expect(result).toEqual(jasmine.any(Category));
                expect(result).toEqual(jasmine.objectContaining(data));
            });

            const req = httpMock.expectOne("categories/1");

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

    describe("#url", () => {
        it("should transform :baseUrl/:modelName:/:id into categories", () => {
            expect(service.url()).toEqual("categories");
        });
        it("should transform :baseUrl/:modelName:/:id into categories/1", () => {
            expect(service.url(1)).toEqual("categories/1");
        });
    });
});

jQuery(function ($) {
	$(document).ready(function () {
		$(".eer-toggle-on-change").each(function () {
			if ($(this).is(":checked")) {
				$($(this).data("show")).show();
				$($(this).data("hide")).hide();
			} else {
				$($(this).data("show")).hide();
				$($(this).data("hide")).show();
			}
		});

		var eer_hide_actions = true;
		$(document).on("click", ".eer-add-new", function () {
			showEditBox();
		}).on("click", ".eer-edit-box .close", function () {
			var editBox = $(".eer-edit-box");
			cleanInputs(editBox);
			editBox.hide();
			$("#eer-discount-edit-form").parsley().reset();
		}).on("change", "#levels_enabled", function () {
			if ($(this).is(":checked")) {
				$(".max_tickets, .max_per_order, .max_leaders, .max_followers").hide();
				if ($("#is_solo").is(":checked")) {
					$(".max_level_tickets, .max_level_per_order").show();
					$(".max_level_leaders, .max_level_followers").hide();
				} else {
					$(".max_level_tickets, .max_level_per_order").hide();
					$(".max_level_leaders, .max_level_followers").show();
				}
			} else {
				if ($("#is_solo").is(":checked")) {
					$(".max_tickets, .max_per_order").show();
				} else {
					$(".max_leaders, .max_followers").show();
				}
			}
		}).on("change", "#is_solo", function () {
			if ($("#levels_enabled").is(":checked")) {
				if ($(this).is(":checked")) {
					$(".max_level_tickets, .max_level_per_order").show();
					$(".max_level_leaders, .max_level_followers").hide();
				} else {
					$(".max_level_tickets, .max_level_per_order").hide();
					$(".max_level_leaders, .max_level_followers").show();
				}
			} else {
				if ($(this).is(":checked")) {
					$(".max_tickets, .max_per_order").show();
					$(".max_leaders, .max_followers").hide();
				} else {
					$(".max_tickets, .max_per_order").hide();
					$(".max_leaders, .max_followers").show();
				}
			}
		}).on("click", ".eer-row .actions button", function () {
			if ($(this).next().is(":visible")) {
				$(this).next().hide();
			} else {
				$(".eer-actions-box").hide();
				$(this).next().show();
				eer_hide_actions = false;
			}
		}).on("change", "input.choose_partner", function () {
			if ($(this).is(":checked")) {
				var partner_input_box = $(this).closest(".eer-partner").find(".eer-info-row.dancing-with-email");
				if ($(this).val() === "0") {
					partner_input_box.hide();
					partner_input_box.find(".eer-info-row-input.dancing-with").prop("required", false);
				} else {
					partner_input_box.show();
					partner_input_box.find(".eer-info-row-input.dancing-with").prop("required", true);
				}

				var partner_name_input_box = $(this).closest(".eer-partner").find(".eer-info-row.dancing-with-name");
				if ($(this).val() === "0") {
					partner_name_input_box.hide();
					partner_name_input_box.find(".eer-info-row-input.dancing-with").prop("required", false);
				} else {
					partner_name_input_box.show();
					if (partner_name_input_box.data("required") === 1) {
						partner_name_input_box.find(".eer-info-row-input.dancing-with-name").prop("required", true);
					}
				}
			}
		});

		function cleanInputs(box) {
			box.find("input.eer-input:not([type=submit]):not([type=checkbox]):not([type=radio])").val("");
			box.find("select.eer-input").val(null);
			box.find("input.eer-input[type=radio]").prop("checked", false);
			box.find("input.eer-input[type=checkbox]").prop("checked", false);

			$.each(box.find("[data-default]"), function () {
				$(this).val($(this).data("default"));
			});

			box.find(".eer-toggle-on-change").each(function () {
				if ($(this).is(":checked")) {
					$($(this).data("show")).show();
					$($(this).data("hide")).hide();
				} else {
					$($(this).data("show")).hide();
					$($(this).data("hide")).show();
				}
			});
		}

		function showEditBox() {
			var editBox = $(".eer-edit-box");
			cleanInputs(editBox);
			editBox.show();
		}

		function prepopulateEventData(row, is_edit) {
			var editBox = $(".eer-edit-box");

			$.each($(editBox).find("input.eer-input:not([type=submit]):not([type=radio]),select.eer-input"), function ($key, $value) {
				if ($($value).attr("type") !== "checkbox") {
					$($value).val($(row).data($($value).attr("id")));
				} else {
					$($value).prop("checked", $(row).data($($value).attr("id")) === 1);
				}
			});
			prepopulate_list($(row), "rf_tshirts", "tshirt_options");
			prepopulate_list($(row), "food", "food_options");

			$.each($(editBox).find("textarea"), function () {
				var areaId = $(this).attr("id");
				/** global: tinyMCE */
				tinyMCE.execCommand("mceRemoveEditor", false, areaId);
				if ($(row).data(areaId)) {
					$("#" + areaId).val($(row).data(areaId).replace(/\\/g, ""));
					$("#" + areaId + "-tmce").click();
				}
			});

			if (is_edit) {
				editBox.find("[name=event_id]").val(row.data("id"));
			}
		}

		function prepopulateTicketData(row, is_edit) {
			var editBox = $(".eer-edit-box");

			$.each($(editBox).find("input.eer-input:not([type=submit]):not([type=checkbox]):not([type=radio]),select.eer-input"), function ($key, $value) {
				if ($($value).hasClass("eer-color-picker")) {
					$($value).wpColorPicker("color", $(row).data($($value).attr("id")));
				} else {
					$($value).val($(row).data($($value).attr("id")));
				}

			});
			$.each($(editBox).find("input.eer-input[type=checkbox]"), function ($key, $value) {
				$($value).prop("checked", $(row).data($($value).attr("id")) === 1);
				if ($($value).is(":checked")) {
					$($($value).data("show")).show();
					$($($value).data("hide")).hide();
				} else {
					$($($value).data("show")).hide();
					$($($value).data("hide")).show();
				}
			});
			prepopulate_list($(row), "levels", "levels");

			$.each($(editBox).find("textarea"), function () {
				var areaId = $(this).attr("id");
				/** global: tinyMCE */
				tinyMCE.execCommand("mceRemoveEditor", false, areaId);
				if ($(row).data(areaId)) {
					$("#" + areaId).val($(row).data(areaId).replace(/\\"/g, "\""));
					$("#" + areaId + "-tmce").click();
				}
			});
			if (is_edit) {
				editBox.find("[name=ticket_id]").val(row.data("id"));
			}
			if ($("#levels_enabled").is(":checked")) {
				$(".max_tickets, .max_per_order, .max_leaders, .max_followers").hide();
			}
		}

		function prepopulateOrderData(row, is_edit) {
			var editBox = $(".eer-edit-box");

			$.each($(editBox).find("input.eer-input:not([type=submit]):not([type=radio]),select.eer-input"), function ($key, $value) {
				if ($($value).attr("type") !== "checkbox") {
					$($value).val($(row).data($($value).attr("id")));
				} else {
					$($value).prop("checked", $(row).data($($value).attr("id")) === 1);
				}
			});

			if (is_edit) {
				editBox.find("[name=order_id]").val(row.data("id"));
			}
		}

		function prepopulateSoldTicketData(row, is_edit) {
			var editBox = $(".eer-edit-box");

			$.each($(editBox).find("input.eer-input:not([type=submit]):not([type=radio]),select.eer-input"), function ($key, $value) {
				if ($($value).attr("type") !== "checkbox") {
					$($value).val($(row).data($($value).attr("id")));
				} else {
					$($value).prop("checked", $(row).data($($value).attr("id")) === 1);
				}
			});

			if (is_edit) {
				editBox.find("[name=sold_ticket_id]").val(row.data("id"));
			}
		}

		$("body").on("click", ".actions.eer-events .eer-action.edit", function () {
			showEditBox();
			prepopulateEventData($(this).closest(".eer-row"), true);
		}).on("click", ".actions.eer-tickets .eer-action.remove", function () {
			var answer = confirm("Do you really want to delete this ticket?");
			if (answer === true) {
				var row = $(this).closest(".eer-row");
				var data = {
					"action": "eer_remove_ticket",
					"ticket_id": $(row).data("id")
				};
				// We can also pass the url value separately from ajaxurl for front end AJAX implementations
				/** global: eer_ajax_object */
				$.post(eer_ajax_object.ajaxurl, data, function (response) {
					location.reload();
				});
			}
		}).on("click", ".actions.eer-tickets .eer-action.remove-forever", function () {
			var answer = confirm("Do you really want to completely delete this ticket?");
			if (answer === true) {
				var row = $(this).closest(".eer-row");
				var data = {
					"action": "eer_remove_ticket_forever",
					"ticket_id": $(row).data("id")
				};
				// We can also pass the url value separately from ajaxurl for front end AJAX implementations
				/** global: eer_ajax_object */
				$.post(eer_ajax_object.ajaxurl, data, function (response) {
					location.reload();
				});
			}
		}).on("click", ".actions.eer-tickets .eer-action.edit", function () {
			showEditBox();
			prepopulateTicketData($(this).closest(".eer-row"), true);
		}).on("click", ".actions.eer-orders .eer-action.edit", function () {
			showEditBox();
			prepopulateOrderData($(this).closest(".eer-row"), true);
		}).on("click", ".actions.eer-sold-tickets .eer-action.edit", function () {
			showEditBox();
			prepopulateSoldTicketData($(this).closest(".eer-row"), true);
		}).on("change", ".eer-toggle-on-change", function () {
			if ($(this).is(":checked")) {
				$($(this).data("show")).show();
				$($(this).data("hide")).hide();
			} else {
				$($(this).data("show")).hide();
				$($(this).data("hide")).show();
			}
		}).on("click", ".eer-add-list-item", function () {
			var row = $(this).parent().find("tr:last");
			var clone = row.clone();
			var count = row.data("key") + 1;
			clone.find("td input").val("");
			clone.find("input").each(function () {
				var name = $(this).attr("name");
				name = name.replace(/\[(\d+)\]/, "[" + parseInt(count) + "]");
				$(this).attr("name", name).attr("id", name);
			});
			clone.find("label").each(function () {
				var name = $(this).attr("for");
				name = name.replace(/\[(\d+)\]/, "[" + parseInt(count) + "]");
				$(this).attr("for", name);
			});
			clone.data("key", count);
			clone.insertAfter(row);
			return false;
		}).on("click", ".eer-action.send-tickets", function () {
			var order = $(this).closest("ul");

			var data = {
				"action": "eer_send_tickets",
				"order_id": order.data("id")
			};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			/** global: eer_ajax_object */
			$.post(eer_ajax_object.ajaxurl, data, function (response) {
			});
		}).on("click", ".eer-orders .eer-action.remove", function () {
			var order = $(this).closest("tr");

			var answer = confirm("Do you really want to delete this order with all tickets?");
			if (answer === true) {
				var data = {
					"action": "eer_remove_order",
					"order_id": order.data("id")
				};
				// We can also pass the url value separately from ajaxurl for front end AJAX implementations
				/** global: eer_ajax_object */
				$.post(eer_ajax_object.ajaxurl, data, function (response) {
					if (response == 1) {
						order.removeClass("eer-status-0").addClass("eer-status-1");
					}
				});
			}
		}).on("click", ".eer-sold-tickets .eer-action.remove", function () {
			var sold_ticket = $(this).closest("tr");

			var answer = confirm("Do you really want to delete this ticket?");
			if (answer === true) {
				var data = {
					"action": "eer_remove_sold_ticket",
					"sold_ticket_id": sold_ticket.data("id")
				};
				// We can also pass the url value separately from ajaxurl for front end AJAX implementations
				/** global: eer_ajax_object */
				$.post(eer_ajax_object.ajaxurl, data, function (response) {
					if (response == 1) {
						sold_ticket.removeClass("eer-status-0").removeClass("eer-status-1").addClass("eer-status-2");
					}
				});
			}
		}).on("click", ".eer-sold-tickets .eer-action.remove-forever", function () {
			var sold_ticket = $(this).closest("tr");

			var answer = confirm("Do you really want to delete this ticket forever?");
			if (answer === true) {
				var data = {
					"action": "eer_remove_sold_ticket_forever",
					"sold_ticket_id": sold_ticket.data("id")
				};
				// We can also pass the url value separately from ajaxurl for front end AJAX implementations
				/** global: eer_ajax_object */
				$.post(eer_ajax_object.ajaxurl, data, function (response) {
					if (response == 1) {
						sold_ticket.remove();
					}
				});
			}
		}).on("click", ".eer-sold-tickets .eer-action.confirm", function () {
			var sold_ticket = $(this).closest("tr");

			var answer = confirm("Do you really want to confirm this ticket?");
			if (answer === true) {
				var data = {
					"action": "eer_confirm_sold_ticket",
					"sold_ticket_id": sold_ticket.data("id")
				};
				// We can also pass the url value separately from ajaxurl for front end AJAX implementations
				/** global: eer_ajax_object */
				$.post(eer_ajax_object.ajaxurl, data, function (response) {
					if (response == 1) {
						sold_ticket.removeClass("eer-status-0").removeClass("eer-status-2").addClass("eer-status-1");
					}
				});
			}
		}).on("click", ".eer_remove_list_item", function () {
			$(this).closest("tr").remove();
			return false;
		}).on("click", ".actions.eer-payment .eer-action.confirm-payment", function () {
			showEditBox();
			prepopulatePaymentData($(this).closest(".eer-row"), false);
			//scroll_to_edit_box();
		}).on("click", ".actions.eer-payment .eer-action.edit", function () {
			showEditBox();
			prepopulatePaymentData($(this).closest(".eer-row"), true);
			//scroll_to_edit_box();
		}).on("click", "[name=eer_payment_submit]", function (e) {
			e.preventDefault();
			var $paymentForm = $(".eer-edit-box");
			var $email = $paymentForm.find("[name=user_email]").val();

			var data = {
				"action": "eer_save_payment",
				"payment": $paymentForm.find("[name=payment]").val(),
				"payment_type": $paymentForm.find("[name=payment_type]").val(),
				"order_id": $paymentForm.find("[name=order_id]").val(),
				"eer_payment_email_confirmation": $paymentForm.find("[name=eer_payment_email_confirmation]").is(":checked"),
				"note": $paymentForm.find("[name=note]").val()
			};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			/** global: ajaxurl */
			$.post(ajaxurl, data, function (response) {
				if (response !== -1) {
					var editBox = $(".eer-edit-box");
					cleanInputs(editBox);
					editBox.hide();

					if ($(".eer-payments-table tr").length > 1) {
						//$(".eer-payments-table").ddTableFilter();
					}
				}
			});
		}).on("click", ".eer-ticket-shop-form input[type=button]", function (e) {
			e.preventDefault();
			var sale_wrapper = $(this).closest(".eer-tickets-sale-wrapper");
			var order_data = {};

			order_data["tickets"] = {};

			var ticket_id = $("input[name=ticket_id]", $(sale_wrapper)).val();
			order_data["tickets"][ticket_id] = {};
			order_data["tickets"][ticket_id]["ticket_id"] = ticket_id;
			order_data["tickets"][ticket_id]["number_of_tickets"] = 1;

			if ($(sale_wrapper).find(".eer-dancing-as-input").length !== 0) {
				order_data["tickets"][ticket_id]["dancing_as"] = $(sale_wrapper).find(".eer-dancing-as-input:checked").val();
			}

			if ($(sale_wrapper).find("[name=level_id]").length !== 0) {
				order_data["tickets"][ticket_id]["level_id"] = $(sale_wrapper).find("[name=level_id]").val();
			}

			if ($(sale_wrapper).find(".eer-choose-partner-input").length !== 0) {
				order_data["tickets"][ticket_id]["choose_partner"] = $(sale_wrapper).find(".eer-choose-partner-input:checked").val();
				order_data["tickets"][ticket_id]["dancing_with"] = $(sale_wrapper).find("[name=dancing_with]").val();

				if ($(sale_wrapper).find(".eer-info-row.dancing-with-name").length !== 0) {
					order_data["tickets"][ticket_id]["dancing_with_name"] = $(sale_wrapper).find("[name=dancing_with_name]").val();
				}
			}

			//load user data
			order_data["user_info"] = {};
			$(sale_wrapper).find(".eer-user-form input, .eer-user-form select, .eer-user-form textarea").each(function (key, input) {
				var value = $(input).val();
				if ($(input).attr("type") === "checkbox") {
					value = $(input).prop("checked");
				}
				order_data["user_info"][$(input).attr("name")] = value;
			});
			//send ajax
			order_data["event_id"] = $(sale_wrapper).find("input[name=event_id]").val();
			var order_data_json = JSON.stringify(order_data);
			var data = {
				"action": "eer_add_ticket_registration",
				"order_data": order_data_json
			};

			/** global: eer_ajax_object */
			$.post(eer_ajax_object.ajaxurl, data, function (response) {
			}).done(function (response) {
				if (response.hasOwnProperty("thank_you_text")) {
					$(sale_wrapper).find(".eer-ticket-shop-form").empty().append(response.thank_you_text);
				} else if (response.hasOwnProperty("errors")) {
					$.each(response.errors.errors, function (key, message) {
						var keys = key.split(".");
						if (keys[0] === "user_info") {
							sale_wrapper.find("[name=" + keys[1] + "]").after("<div class=\"eer-error\">" + message[0] + "</div>");
						} else if (keys[0] === "tickets") {
							if (keys[1] === "all") {

							} else {
								sale_wrapper.find(".eer-ticket-to-buy[data-id=" + keys[1] + "]").append("<div class=\"eer-error\">" + message[0] + "</div>");
							}
							if (keys[2] === "full") {
								//$(".eer-ticket[data-id=" + keys[1] + "]").addClass('eer-sold');
							}
						}
					});
					$("html, body").animate({
						scrollTop: sale_wrapper.find(".eer-form-tickets").offset().top - 50
					}, 2000);
				}
			});
		});

		$("input[name=\"eer-select-all\"]").on("change", function () {
			$("input[name=\"eer_choosed_payments[]\"]").prop("checked", $(this).is(":checked"));
		});

		function prepopulate_list(row, id, data) {
			if (row.attr("data-" + data)) {
				var level_row = $("#" + id + " .eer_list_items tbody tr");
				$("#" + id + " .eer_list_items tbody").empty();
				$.each(row.data(data), function (key, level) {
					var clone = level_row.clone();
					var count = level.key;
					clone.find("td input").val("");
					clone.find("input").each(function () {
						var name = $(this).attr("name");
						name = name.replace(/\[(\d+)\]/, "[" + parseInt(count) + "]");
						$(this).attr("name", name).attr("id", name).val(level[$(this).data("name")]);
					});
					clone.find("label").each(function () {
						var name = $(this).attr("for");
						name = name.replace(/\[(\d+)\]/, "[" + parseInt(count) + "]");
						$(this).attr("for", name);
					});
					clone.data("key", count);
					$("#" + id + " .eer_list_items tbody").append(clone);
				});
			}
		}

		function prepopulatePaymentData(row, is_edit) {
			var editBox = $(".eer-edit-box");
			editBox.find("[name=payment_type]").val("paid");
			editBox.find("[name=payment]").val(parseInt(row.data("to_pay")));
			editBox.find("[name=order_id]").val(row.data("order_id"));
			$("tr.payment").show();

			if (is_edit) {
				editBox.find("[name=payment_id]").val(row.data("id"));
				editBox.find("[name=payment]").val(parseInt(row.data("payment")));
			}
		}

		function eer_run_spinner(wrapper) {
			var opts = {
				lines: 12, // The number of lines to draw
				length: 30, // The length of each line
				width: 17, // The line thickness
				radius: 45, // The radius of the inner circle
				scale: 1, // Scales overall size of the spinner
				corners: 1, // Corner roundness (0..1)
				color: "#ffffff", // CSS color or array of colors
				fadeColor: "transparent", // CSS color or array of colors
				speed: 0.7, // Rounds per second
				rotate: 0, // The rotation offset
				animation: "spinner-line-fade-quick", // The CSS animation name for the lines
				direction: 1, // 1: clockwise, -1: counterclockwise
				zIndex: 2e9, // The z-index (defaults to 2000000000)
				className: "spinner", // The CSS class to assign to the spinner
				top: "80%", // Top position relative to parent
				left: "50%", // Left position relative to parent
				shadow: "0 0 1px transparent", // Box-shadow for the lines
				position: "absolute" // Element positioning
			};

			var spinner = new Spinner(opts).spin();
			$(".eer-spinner-bg", wrapper).show();
			$(wrapper).append(spinner.el);
			return spinner;
		}

		function eer_stop_spinner(spinner, wrapper) {
			spinner.stop();
			$(".eer-spinner-bg", wrapper).hide();
		}

		if ($(".eer-datatable").length) {
			$(".eer-datatable").DataTable({
				aLengthMenu: [
					[25, 50, 100, 200, -1],
					[25, 50, 100, 200, "All"]
				],
				iDisplayLength: 100,
				order: [[0, "desc"]],
				"aoColumnDefs": [
					{"bSortable": false, "aTargets": ["no-sort"]}
				],
				dom: "lBfrtip",
				buttons: [
					"colvis"
				],
				columnDefs: [{
					"targets": "no-sort",
					"orderable": false
				}],
				initComplete: function () {
					this.api().columns().every(function () {
						var column = this;
						if (!$(column.header()).hasClass("filter-disabled")) {
							var select = $("<select><option value=\"\">" + $(column.header()).text() + "</option></select>")
							.appendTo($(column.header()).empty())
							.on("change", function () {
								var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
								);

								column
								.search(val ? "^" + val + "$" : "", true, false)
								.draw();
							});

							column.data().unique().sort().each(function (d) {
								select.append("<option value=\"" + d + "\">" + d + "</option>");
							});
						}
					});
				}
			});
		}
		if ($(".eer-color-picker").length > 0) {
			$(".eer-color-picker").wpColorPicker();
		}

		$(document).on("click", "body", function () {
			if (eer_hide_actions) {
				$(".eer-actions-box").hide();
			}
			eer_hide_actions = true;
		});
	});
})
;

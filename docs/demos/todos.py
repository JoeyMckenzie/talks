# TODO: add input validation on user-submitted forms
def process_form(data):
    return save_to_db(data)

# TODO: replace this with a proper connection pool
def get_connection():
    return create_new_connection()

# TODO: implement retry logic for flaky third-party API
def call_payment_api(amount):
    return requests.post(PAYMENT_URL, json={"amount": amount})

# TODO: this timeout is way too aggressive for large exports
def export_report(report_id):
    return generate_csv(report_id, timeout=5)

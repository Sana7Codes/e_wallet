from graphviz import Digraph
import matplotlib.pyplot as plt
import networkx as nx
# Create the component diagram
diagram = Digraph('Digital_Wallet_Components',
                  filename='digital_wallet_component_diagram', format='png')

# Define components
diagram.node('Frontend', '<<component>>\nFrontend',
             shape='tab', style='filled', fillcolor='lightblue')
diagram.node('Backend', '<<component>>\nBackend', shape='tab',
             style='filled', fillcolor='lightyellow')
diagram.node('Database', '<<component>>\nDatabase', shape='tab',
             style='filled', fillcolor='lightsteelblue')

# Define connections with UML notation
diagram.edge('Frontend', 'Backend', label='◯ Sends API Calls',
             arrowhead='none', arrowtail='odot')
diagram.edge('Backend', 'Database', label='⌂ Stores Data',
             arrowhead='none', arrowtail='inv')

# Render the diagram
diagram.render('digital_wallet_component_diagram', format='png', cleanup=True)

print("✅ Diagram generated: digital_wallet_component_diagram.png")


# Create a directed graph
G = nx.DiGraph()

# Define nodes for Frontend Components
frontend_components = {
    "A": "Registration & Profile Forms\n(Frontend)",
    "B": "Identity Verification UI\n(Frontend)",
    "C": "Transaction Initiation Interface\n(Frontend)",
    "D": "QR Code Scanning Module\n(Frontend)",
    "E": "Self-Service Interface\n(Frontend)",
    "F": "User Dashboard & Notifications\n(Frontend)"
}

# Define nodes for Backend Components
backend_components = {
    "G": "User Management & Profile Module\n(Backend)",
    "H": "KYC/Compliance Module\n(Backend)",
    "I": "Transaction Processing Module\n(Backend)",
    "J": "Payment Processing & QR Code Module\n(Backend)",
    "K": "API Gateway / Integration Module\n(Backend)",
    "L": "Logging & Monitoring System\n(Backend)",
    "M": "Analytics & Reporting Module\n(Backend)",
    "N": "Database & Backup System\n(Backend)"
}

# Define External Systems and Admin Tools
external_admin = {
    "O": "External Systems\n(Payment Gateways, Social Logins)",
    "P": "Admin Dashboards & Monitoring Tools"
}

# Add all nodes to the graph
for node_id, label in {**frontend_components, **backend_components, **external_admin}.items():
    G.add_node(node_id, label=label)

# Define relationships (edges)
edges = [
    # Frontend to Backend
    ("A", "G", "Sends registration & profile data"),
    ("B", "H", "Sends ID documents for verification"),
    ("C", "I", "Sends transaction initiation requests"),
    ("D", "J", "Sends QR code scan data"),
    ("E", "G", "Sends self-service commands"),

    # Backend to Frontend
    ("G", "F", "Sends profile updates & confirmations"),
    ("H", "F", "Sends verification status updates"),
    ("I", "F", "Sends transaction confirmations"),
    ("J", "F", "Sends QR codes & payment confirmations"),

    # External Systems Communication
    ("O", "K", "Sends API requests"),
    ("K", "O", "Sends API responses"),

    # Backend Monitoring & Administration
    ("G", "L", "Logs and alerts"),
    ("H", "L", "Logs and alerts"),
    ("I", "L", "Logs and alerts"),
    ("J", "L", "Logs and alerts"),
    ("K", "L", "Logs and alerts"),
    ("L", "P", "Sends logs & metrics"),

    # Analytics & Reporting Flow
    ("N", "M", "Stores transaction & user data"),
    ("M", "P", "Sends reports & data exports")
]

# Add edges to the graph
for src, dest, label in edges:
    G.add_edge(src, dest, label=label)

# Position nodes using a shell layout
pos = nx.shell_layout(G)

# Define node colors based on their category
color_map = {
    **{key: "lightblue" for key in frontend_components},  # Frontend Components
    **{key: "lightyellow" for key in backend_components},  # Backend Components
    # External Systems & Admin Tools
    **{key: "lightgray" for key in external_admin}
}

node_colors = [color_map[node] for node in G.nodes]

# Draw the graph
plt.figure(figsize=(15, 10))
nx.draw(G, pos, with_labels=True, labels={node: G.nodes[node]["label"] for node in G.nodes},
        node_color=node_colors, edge_color="black", node_size=3000, font_size=8, font_weight="bold", arrows=True)

# Draw edge labels
edge_labels = {(src, dest): label for src, dest, label in edges}
nx.draw_networkx_edge_labels(G, pos, edge_labels=edge_labels, font_size=7)

# Show the structured flow diagram
plt.title("Digital Wallet Platform - Structured Flow Diagram",
          fontsize=14, fontweight='bold')
plt.show()
